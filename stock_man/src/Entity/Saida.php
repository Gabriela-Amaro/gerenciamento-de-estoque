<?php

namespace App\Entity;

use App\Repository\SaidaRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SaidaRepository::class)]
class Saida
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $quantidade = null;

    #[ORM\ManyToOne(inversedBy: 'saidas')]
    #[ORM\JoinColumn(nullable: false)]
    private ?transacao $transacao_fk = null;

    #[ORM\ManyToOne(inversedBy: 'saidas')]
    #[ORM\JoinColumn(nullable: false)]
    private ?produto $produto_fk = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuantidade(): ?int
    {
        return $this->quantidade;
    }

    public function setQuantidade(int $quantidade): static
    {
        $this->quantidade = $quantidade;

        return $this;
    }

    public function getTransacaoFk(): ?transacao
    {
        return $this->transacao_fk;
    }

    public function setTransacaoFk(?transacao $transacao_fk): static
    {
        $this->transacao_fk = $transacao_fk;

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

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): static
    {
        $this->created_at = $created_at;

        return $this;
    }
}
