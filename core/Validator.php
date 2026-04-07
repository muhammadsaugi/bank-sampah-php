<?php
// ─── core/Validator.php ───
// Validasi input reusable. Kumpulkan error, cek dengan hasErrors().

class Validator
{
    private array $errors = [];
    private array $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * Field wajib diisi.
     */
    public function required(string $field, string $label = ''): static
    {
        $label = $label ?: ucfirst($field);
        if (!isset($this->data[$field]) || trim((string)$this->data[$field]) === '') {
            $this->errors[$field] = "$label wajib diisi.";
        }
        return $this;
    }

    /**
     * Panjang maksimum string.
     */
    public function maxLength(string $field, int $max, string $label = ''): static
    {
        $label = $label ?: ucfirst($field);
        if (isset($this->data[$field]) && mb_strlen((string)$this->data[$field]) > $max) {
            $this->errors[$field] = "$label maksimal $max karakter.";
        }
        return $this;
    }

    /**
     * Panjang minimum string.
     */
    public function minLength(string $field, int $min, string $label = ''): static
    {
        $label = $label ?: ucfirst($field);
        if (isset($this->data[$field]) && mb_strlen((string)$this->data[$field]) < $min) {
            $this->errors[$field] = "$label minimal $min karakter.";
        }
        return $this;
    }

    /**
     * Harus berupa email valid.
     */
    public function email(string $field, string $label = 'Email'): static
    {
        if (!empty($this->data[$field]) && !filter_var($this->data[$field], FILTER_VALIDATE_EMAIL)) {
            $this->errors[$field] = "$label tidak valid.";
        }
        return $this;
    }

    /**
     * Harus berupa integer positif.
     */
    public function integer(string $field, string $label = ''): static
    {
        $label = $label ?: ucfirst($field);
        if (!empty($this->data[$field]) && filter_var($this->data[$field], FILTER_VALIDATE_INT) === false) {
            $this->errors[$field] = "$label harus berupa angka bulat.";
        }
        return $this;
    }

    /**
     * Harus berupa angka positif (lebih dari 0).
     */
    public function positiveNumber(string $field, string $label = ''): static
    {
        $label = $label ?: ucfirst($field);
        $val   = filter_var($this->data[$field] ?? 0, FILTER_VALIDATE_FLOAT);
        if ($val === false || $val <= 0) {
            $this->errors[$field] = "$label harus berupa angka lebih dari 0.";
        }
        return $this;
    }

    /**
     * Harus berupa format tanggal Y-m-d.
     */
    public function date(string $field, string $label = ''): static
    {
        $label = $label ?: ucfirst($field);
        if (!empty($this->data[$field])) {
            $d = DateTime::createFromFormat('Y-m-d', $this->data[$field]);
            if (!$d || $d->format('Y-m-d') !== $this->data[$field]) {
                $this->errors[$field] = "$label harus berupa tanggal valid (YYYY-MM-DD).";
            }
        }
        return $this;
    }

    /**
     * Nilai harus ada dalam daftar tertentu.
     */
    public function inList(string $field, array $list, string $label = ''): static
    {
        $label = $label ?: ucfirst($field);
        if (isset($this->data[$field]) && !in_array($this->data[$field], $list, true)) {
            $this->errors[$field] = "$label tidak valid.";
        }
        return $this;
    }

    /**
     * Apakah ada error?
     */
    public function hasErrors(): bool
    {
        return !empty($this->errors);
    }

    /**
     * Kembalikan semua error.
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * Kembalikan error pertama sebagai string (untuk flash message).
     */
    public function firstError(): string
    {
        return reset($this->errors) ?: '';
    }
}
