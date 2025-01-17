<?php

namespace App\Entity;

use App\Repository\LoteRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LoteRepository::class)]
class Lote
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 9, scale: 2)]
    private ?string $preco_unitario = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 9, scale: 2)]
    private ?string $custo_unitario = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $data_entrada = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $validade = null;

    #[ORM\Column]
    private ?int $quantidade_inicial = null;

    #[ORM\ManyToOne(inversedBy: 'lotes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?funcionario $funcionario_fk = null;

    #[ORM\ManyToOne(inversedBy: 'lotes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?produto $produto_fk = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPrecoUnitario(): ?string
    {
        return $this->preco_unitario;
    }

    public function setPrecoUnitario(string $preco_unitario): static
    {
        $this->preco_unitario = $preco_unitario;

        return $this;
    }

    public function getCustoUnitario(): ?string
    {
        return $this->custo_unitario;
    }

    public function setCustoUnitario(string $custo_unitario): static
    {
        $this->custo_unitario = $custo_unitario;

        return $this;
    }

    public function getDataEntrada(): ?\DateTimeImmutable
    {
        return $this->data_entrada;
    }

    public function setDataEntrada(\DateTimeImmutable $data_entrada): static
    {
        $this->data_entrada = $data_entrada;

        return $this;
    }

    public function getValidade(): ?\DateTimeImmutable
    {
        return $this->validade;
    }

    public function setValidade(\DateTimeImmutable $validade): static
    {
        $this->validade = $validade;

        return $this;
    }

    public function getQuantidadeInicial(): ?int
    {
        return $this->quantidade_inicial;
    }

    public function setQuantidadeInicial(int $quantidade_inicial): static
    {
        $this->quantidade_inicial = $quantidade_inicial;

        return $this;
    }

    public function getFuncionarioFk(): ?funcionario
    {
        return $this->funcionario_fk;
    }

    public function setFuncionarioFk(?funcionario $funcionario_fk): static
    {
        $this->funcionario_fk = $funcionario_fk;

        return $this;
    }

    public function getProdutoFk(): ?produto
    {
        return $this->produto_fk;
    }

    public function setProdutoFk(?produto $produto_fk): static
    {
        $this->produto_fk = $produto_fk;

        return $this;
    }
}
