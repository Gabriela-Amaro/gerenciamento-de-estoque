<?php

namespace App\Controller;

use App\Entity\Produto;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use DateTimeZone;

final class ProdutoController extends AbstractController
{
    #[Route('/produto', name: 'produto_list')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $repository = $entityManager->getRepository(Produto::class);

        $produtos = $repository->findAll();

        return $this->render('produto/index.html.twig', [
            'produtos' => $produtos,
        ]);
    }

    #[Route('/produto/create', name: 'produto_create')]
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        $data = $request->request->all();

        $produto = new Produto();
        $produto->setNome($data['nome']);
        $produto->setCategoria($data['categoria']);
        $produto->setDescricao($data['descricao']);
        $produto->setQuantidade(0);
        $produto->setCreatedAt(new \DateTimeImmutable('now', new DateTimeZone('America/Sao_Paulo')));
        $produto->setUpdatedAt(new \DateTimeImmutable('now', new DateTimeZone('America/Sao_Paulo')));

        $entityManager->persist($produto);

        $entityManager->flush();

        return $this->render('produto/index.html.twig', [
            'produto' => $produto,
        ]);
    }
    
    #[Route('/produto/{id}', name: 'produto_update')]
    public function update(Request $request, EntityManagerInterface $entityManager, int $id): Response
    {
        $repository = $entityManager->getRepository(Produto::class);

        $produto = $repository->find($id);

        if(!$produto) throw $this->createNotFoundException();

        $data = $request->request->all();

        $produto->setNome($data['nome']);
        $produto->setCategoria($data['categoria']);
        $produto->setDescricao($data['descricao']);
        $produto->setUpdatedAt(new \DateTimeImmutable('now', new DateTimeZone('America/Sao_Paulo')));

        $entityManager->persist($produto);
        
        $entityManager->flush();

        return $this->render('produto/index.html.twig', [
            'produtos' => $produto,
        ]);
    }
}
