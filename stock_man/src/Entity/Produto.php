<?php

namespace App\Entity;

use App\Repository\ProdutoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProdutoRepository::class)]
class Produto
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nome = null;

    #[ORM\Column(length: 255)]
    private ?string $categoria = null;

    #[ORM\Column]
    private ?int $quantidade = null;

    /**
     * @var Collection<int, Lote>
     */
    #[ORM\OneToMany(targetEntity: Lote::class, mappedBy: 'produto_fk')]
    private Collection $lotes;

    /**
     * @var Collection<int, Saida>
     */
    #[ORM\OneToMany(targetEntity: Saida::class, mappedBy: 'produto_fk')]
    private Collection $saidas;

    public function __construct()
    {
        $this->lotes = new ArrayCollection();
        $this->saidas = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNome(): ?string
    {
        return $this->nome;
    }

    public function setNome(string $nome): static
    {
        $this->nome = $nome;

        return $this;
    }

    public function getCategoria(): ?string
    {
        return $this->categoria;
    }

    public function setCategoria(string $categoria): static
    {
        $this->categoria = $categoria;

        return $this;
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

    /**
     * @return Collection<int, Lote>
     */
    public function getLotes(): Collection
    {
        return $this->lotes;
    }

    public function addLote(Lote $lote): static
    {
        if (!$this->lotes->contains($lote)) {
            $this->lotes->add($lote);
            $lote->setProdutoFk($this);
        }

        return $this;
    }

    public function removeLote(Lote $lote): static
    {
        if ($this->lotes->removeElement($lote)) {
            // set the owning side to null (unless already changed)
            if ($lote->getProdutoFk() === $this) {
                $lote->setProdutoFk(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Saida>
     */
    public function getSaidas(): Collection
    {
        return $this->saidas;
    }

    public function addSaida(Saida $saida): static
    {
        if (!$this->saidas->contains($saida)) {
            $this->saidas->add($saida);
            $saida->setProdutoFk($this);
        }

        return $this;
    }

    public function removeSaida(Saida $saida): static
    {
        if ($this->saidas->removeElement($saida)) {
            // set the owning side to null (unless already changed)
            if ($saida->getProdutoFk() === $this) {
                $saida->setProdutoFk(null);
            }
        }

        return $this;
    }
}
