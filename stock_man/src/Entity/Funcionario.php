<?php

namespace App\Entity;

use App\Repository\FuncionarioRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FuncionarioRepository::class)]
class Funcionario
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nome = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    private ?string $senha = null;

    #[ORM\Column(length: 255)]
    private ?string $cargo = null;

    /**
     * @var Collection<int, Lote>
     */
    #[ORM\OneToMany(targetEntity: Lote::class, mappedBy: 'funcionario_fk')]
    private Collection $lotes;

    /**
     * @var Collection<int, Transacao>
     */
    #[ORM\OneToMany(targetEntity: Transacao::class, mappedBy: 'funcionario_fk')]
    private Collection $transacoes;

    public function __construct()
    {
        $this->lotes = new ArrayCollection();
        $this->transacoes = new ArrayCollection();
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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getSenha(): ?string
    {
        return $this->senha;
    }

    public function setSenha(string $senha): static
    {
        $this->senha = $senha;

        return $this;
    }

    public function getCargo(): ?string
    {
        return $this->cargo;
    }

    public function setCargo(string $cargo): static
    {
        $this->cargo = $cargo;

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
            $lote->setFuncionarioFk($this);
        }

        return $this;
    }

    public function removeLote(Lote $lote): static
    {
        if ($this->lotes->removeElement($lote)) {
            // set the owning side to null (unless already changed)
            if ($lote->getFuncionarioFk() === $this) {
                $lote->setFuncionarioFk(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Transacao>
     */
    public function getTransacoes(): Collection
    {
        return $this->transacoes;
    }

    public function addTransaco(Transacao $transaco): static
    {
        if (!$this->transacoes->contains($transaco)) {
            $this->transacoes->add($transaco);
            $transaco->setFuncionarioFk($this);
        }

        return $this;
    }

    public function removeTransaco(Transacao $transaco): static
    {
        if ($this->transacoes->removeElement($transaco)) {
            // set the owning side to null (unless already changed)
            if ($transaco->getFuncionarioFk() === $this) {
                $transaco->setFuncionarioFk(null);
            }
        }

        return $this;
    }
}
