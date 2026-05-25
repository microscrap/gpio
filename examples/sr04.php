<?php

/**
 * HC-SR04 Ultrasonic Distance Sensor
 *
 * Wiring:
 *   VCC  → 5 V
 *   GND  → GND
 *   TRIG → GPIO 24  (output — we send the trigger pulse)
 *   ECHO → GPIO 22  (input  — we measure the reflected pulse width)
 *
 * How it works:
 *   1. Pull TRIG HIGH for 10 µs to fire a burst of 8 ultrasonic pulses.
 *   2. The sensor pulls ECHO HIGH until the burst returns.
 *   3. Echo HIGH duration → distance:
 *        distance_cm = duration_µs / 58.0
 *      (speed of sound round-trip ≈ 58 µs/cm at room temperature)
 *
 * Edge-event timestamps come from the kernel monotonic clock, so we get
 * sub-microsecond accuracy without any userspace timing loops.
 */

require_once __DIR__.'/../../../vendor/autoload.php';

use Microscrap\Bindings\GPIO\Enums\LineDirection;
use Microscrap\Bindings\GPIO\Enums\LineEdge;
use Microscrap\Bindings\GPIO\Enums\LineValue;

const CHIP_PATH = '/dev/gpiochip0';
const TRIG_OFFSET = 24;
const ECHO_OFFSET = 22;
const MEASUREMENTS = 100;

// ---------------------------------------------------------------------------
// Open chip
// ---------------------------------------------------------------------------

$chip = gpiod_chip_open(CHIP_PATH);
if ($chip === null) {
    fwrite(STDERR, 'Failed to open '.CHIP_PATH."\n");
    exit(1);
}

// ---------------------------------------------------------------------------
// Configure lines
//   TRIG: output, starts LOW
//   ECHO: input, both-edge detection so we catch rising and falling events
// ---------------------------------------------------------------------------

$trig_settings = gpiod_line_settings_new();
gpiod_line_settings_set_direction($trig_settings, LineDirection::Output);
gpiod_line_settings_set_output_value($trig_settings, LineValue::Inactive);

$echo_settings = gpiod_line_settings_new();
gpiod_line_settings_set_direction($echo_settings, LineDirection::Input);
gpiod_line_settings_set_edge_detection($echo_settings, LineEdge::Both);

$line_config = gpiod_line_config_new();
gpiod_line_config_add_line_settings($line_config, [TRIG_OFFSET], $trig_settings);
gpiod_line_config_add_line_settings($line_config, [ECHO_OFFSET], $echo_settings);

$req_config = gpiod_request_config_new();
gpiod_request_config_set_consumer($req_config, 'sr04-example');

$request = gpiod_chip_request_lines($chip, $req_config, $line_config);
if ($request === null) {
    fwrite(STDERR, "Failed to request lines — are they already in use?\n");
    gpiod_chip_close($chip);
    exit(1);
}

// ---------------------------------------------------------------------------
// One edge-event buffer, capacity 1. We read one event at a time:
// first the rising edge (echo start), then the falling edge (echo end).
// ---------------------------------------------------------------------------

$event_buffer = gpiod_edge_event_buffer_new(1);

// ---------------------------------------------------------------------------
// Measurement loop
// ---------------------------------------------------------------------------

for ($i = 1; $i <= MEASUREMENTS; $i++) {

    // Guarantee TRIG is LOW before firing (belt-and-suspenders)
    gpiod_line_request_set_value($request, TRIG_OFFSET, LineValue::Inactive);
    time_nanosleep(0, 2_000); // 2 µs settle

    // Fire the 10 µs trigger pulse
    gpiod_line_request_set_value($request, TRIG_OFFSET, LineValue::Active);
    time_nanosleep(0, 10_000); // 10 µs
    gpiod_line_request_set_value($request, TRIG_OFFSET, LineValue::Inactive);

    // --- Rising edge: ECHO goes HIGH ---
    // The sensor needs up to ~500 µs to start responding; allow 5 ms headroom.
    $ready = gpiod_line_request_wait_edge_events($request, 5_000_000); // 5 ms
    if ($ready !== 1) {
        printf("Measurement #%d: timeout waiting for echo start\n", $i);
        usleep(60_000);

        continue;
    }

    $n = gpiod_line_request_read_edge_events($request, $event_buffer, 1);
    if ($n < 1) {
        printf("Measurement #%d: no rising edge event read\n", $i);
        usleep(60_000);

        continue;
    }

    $rising_ns = gpiod_edge_event_buffer_get_event($event_buffer, 0)->timestamp_ns;

    // --- Falling edge: ECHO goes LOW ---
    // HC-SR04 max range ≈ 400 cm → echo ≤ 23 200 µs → allow 25 ms.
    $ready = gpiod_line_request_wait_edge_events($request, 25_000_000); // 25 ms
    if ($ready !== 1) {
        printf("Measurement #%d: timeout waiting for echo end (object out of range?)\n", $i);
        usleep(60_000);

        continue;
    }

    $n = gpiod_line_request_read_edge_events($request, $event_buffer, 1);
    if ($n < 1) {
        printf("Measurement #%d: no falling edge event read\n", $i);
        usleep(60_000);

        continue;
    }

    $falling_ns = gpiod_edge_event_buffer_get_event($event_buffer, 0)->timestamp_ns;

    // --- Calculate distance ---
    $duration_us = ($falling_ns - $rising_ns) / 1_000.0;
    $distance_cm = $duration_us / 58.0;
    $distance_in = $duration_us / 148.0;

    printf(
        "Measurement #%2d: echo = %7.1f µs  →  %5.2f cm  (%4.2f in)\n",
        $i,
        $duration_us,
        $distance_cm,
        $distance_in,
    );

    // HC-SR04 datasheet requires ≥ 60 ms between trigger pulses
    usleep(60_000);
}

// ---------------------------------------------------------------------------
// Cleanup — TRIG is driven LOW by the driver when the request is released
// ---------------------------------------------------------------------------

gpiod_line_request_release($request);
gpiod_chip_close($chip);
