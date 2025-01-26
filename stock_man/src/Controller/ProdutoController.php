<?php

namespace App\Controller;

use App\Entity\Produto;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use DateTimeZone;
use App\Enum\ProdutoCategoria;
use PHPUnit\Util\Json;
use Symfony\Component\HttpFoundation\JsonResponse;

final class ProdutoController extends AbstractController
{
    #[Route('/produto', name: 'produtos_list', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $repository = $entityManager->getRepository(Produto::class);

        $produtos = $repository->findAll();

        // Pega as opções do enum
        $categorias = ProdutoCategoria::getOptions();

        return $this->render('produto/index.html.twig', [
            'produtos' => $produtos,
            'categorias' => $categorias,
        ]);
    }

    #[Route('/produto', name: 'produto_create', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        if($request->headers->get('Content-Type') == 'application/json'){
            $data = $request->toArray();
        } else {
            $data = $request->request->all();
        }

        // converte a categoria
        $categoria = ProdutoCategoria::tryFrom($data['categoria']); // Tenta converter a string para enum

        if (!$categoria) {
            throw new \InvalidArgumentException("Categoria inválida!"); // Trate o caso de falha na conversão
        }

        $produto = new Produto();
        $produto->setNome($data['nome']);
        $produto->setCategoria($categoria);
        $produto->setDescricao($data['descricao']);
        $produto->setQuantidade(0);
        $produto->setCreatedAt(new \DateTimeImmutable('now', new DateTimeZone('America/Sao_Paulo')));
        $produto->setUpdatedAt(new \DateTimeImmutable('now', new DateTimeZone('America/Sao_Paulo')));

        $entityManager->persist($produto);

        $entityManager->flush();

        return $this->json([
            'message' => 'Produto criado com sucesso!',
        ], 201);
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
