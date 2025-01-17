<?php

namespace App\Entity;

use App\Repository\TransacaoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TransacaoRepository::class)]
class Transacao
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\ManyToOne(inversedBy: 'transacoes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?funcionario $funcionario_fk = null;

    /**
     * @var Collection<int, Saida>
     */
    #[ORM\OneToMany(targetEntity: Saida::class, mappedBy: 'transacao_fk')]
    private Collection $saidas;

    public function __construct()
    {
        $this->saidas = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getFuncionarioFk(): ?funcionario
    {
        return $this->funcionario_fk;
    }

    public function setFuncionarioFk(?funcionario $funcionario_fk): static
    {
        $this->funcionario_fk = $funcionario_fk;

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
            $saida->setTransacaoFk($this);
        }

        return $this;
    }

    public function removeSaida(Saida $saida): static
    {
        if ($this->saidas->removeElement($saida)) {
            // set the owning side to null (unless already changed)
            if ($saida->getTransacaoFk() === $this) {
                $saida->setTransacaoFk(null);
            }
        }

        return $this;
    }
}
