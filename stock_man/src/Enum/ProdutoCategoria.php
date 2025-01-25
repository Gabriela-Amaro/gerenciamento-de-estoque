<?php

namespace App\Enum;

enum ProdutoCategoria: int 
{
    case CATEGORIA1 = 1;
    case CATEGORIA2 = 2;
    case CATEGORIA3 = 3;
    case CATEGORIA4 = 4;
    case CATEGORIA5 = 5;

    public function getDescription(): string
    {
        return match ($this) {
            self::CATEGORIA1 => "Categoria 1",
            self::CATEGORIA2 => "Categoria 2",
            self::CATEGORIA3 => "Categoria 3",
            self::CATEGORIA4 => "Categoria 4",
            self::CATEGORIA5 => "Categoria 5",
        };
    }

    public static function getOptions(): array
    {
        return array_map(fn(self $categoria) => [
            'value' => $categoria->value,
            'descricao' => $categoria->getDescription(),
        ], self::cases());
    }
}