<?php

namespace Microscrap\Bindings\GPIO\DataObjects;

/**
 * struct gpio_v2_line_request (linux/gpio.h)
 *
 * Binary layout (592 bytes, little-endian):
 *   offset   0 : __u32 offsets[64] (256 bytes)
 *   offset 256 : __u8  consumer[32]
 *   offset 288 : struct gpio_v2_line_config config (272 bytes)
 *   offset 560 : __u32 num_lines
 *   offset 564 : __u32 event_buffer_size
 *   offset 568 : __u32 padding[5]
 *   offset 588 : __s32 fd
 */
final class GPIOV2LineRequest
{
    /**
     * @param array<int, int> $offsets
     */
    public function __construct(
        public array $offsets = [],
        public string $consumer = '',
        public ?GPIOV2LineConfig $config = null,
        public int $num_lines = 0,
        public int $event_buffer_size = 0,
        public int $fd = -1,
    ) {}

    public function toBuffer(): string
    {
        $num_lines = min(64, min($this->num_lines, count($this->offsets))); // GPIO_V2_LINES_MAX
        $consumer = str_pad(substr($this->consumer, 0, 32), 32, "\0");

        $offsets_buffer = '';
        for ($index = 0; $index < 64; $index++) { // GPIO_V2_LINES_MAX
            $offset = $index < $num_lines ? (int) $this->offsets[$index] : 0;
            $offsets_buffer .= pack('V', $offset);
        }

        $config = $this->config instanceof GPIOV2LineConfig
            ? $this->config->toBuffer()
            : str_repeat("\0", 272);

        return $offsets_buffer
            . $consumer
            . $config
            . pack('V', $num_lines)
            . pack('V', $this->event_buffer_size)
            . str_repeat("\0", 20)
            . pack('V', static::s32ToU32($this->fd));
    }

    public static function fromBuffer(string $buffer): ?self
    {
        if (strlen($buffer) < 592) {
            return null;
        }

        $num_lines = unpack('V', substr($buffer, 560, 4))[1];
        $num_lines = min(64, $num_lines); // GPIO_V2_LINES_MAX

        $offsets = [];
        for ($index = 0; $index < $num_lines; $index++) {
            $offsets[] = unpack('V', substr($buffer, $index * 4, 4))[1];
        }

        $flags = unpack('P', substr($buffer, 288, 8))[1];
        $num_attrs = unpack('V', substr($buffer, 296, 4))[1];

        return new self(
            offsets: $offsets,
            consumer: rtrim(substr($buffer, 256, 32), "\0"),
            config: new GPIOV2LineConfig(
                flags: $flags,
                num_attrs: $num_attrs,
                attrs: [],
            ),
            num_lines: $num_lines,
            event_buffer_size: unpack('V', substr($buffer, 564, 4))[1],
            fd: static::u32ToS32(unpack('V', substr($buffer, 588, 4))[1]),
        );
    }

    private static function u32ToS32(int $value): int
    {
        return $value >= 0x80000000
            ? $value - 0x100000000
            : $value;
    }

    private static function s32ToU32(int $value): int
    {
        return $value < 0
            ? $value + 0x100000000
            : $value;
    }
}
