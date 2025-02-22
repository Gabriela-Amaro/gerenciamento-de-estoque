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
    public function index(EntityManagerInterface $entityManager, Request $request): Response
    {
        $page = $request->query->getInt('page', 1);
        $search = $request->query->get('search', '');
        $limit = 10;

        $repository = $entityManager->getRepository(Produto::class);
        
        // Criar QueryBuilder para busca flexível
        $qb = $repository->createQueryBuilder('p')
            ->orderBy('p.id', 'DESC');
        
        // Se houver uma busca, adiciona o filtro
        if ($search) {
            $qb->where('LOWER(p.nome) LIKE LOWER(:search)')
               ->setParameter('search', '%' . $search . '%');
        }
        
        // Obter total de produtos com filtro
        $total = count($qb->getQuery()->getResult());
        
        // Aplicar paginação
        $qb->setFirstResult(($page - 1) * $limit)
           ->setMaxResults($limit);
        
        $produtos = $qb->getQuery()->getResult();
        $totalPages = ceil($total / $limit);

        // Pega as opções do enum
        $categorias = ProdutoCategoria::getOptions();

        // Se for uma requisição AJAX, renderiza apenas o template parcial
        if ($request->headers->get('X-Requested-With') === 'XMLHttpRequest') {
            return $this->render('produto/_table.html.twig', [
                'produtos' => $produtos,
                'currentPage' => $page,
                'totalPages' => $totalPages,
                'total' => $total,
                'limit' => $limit
            ]);
        }

        return $this->render('produto/index.html.twig', [
            'produtos' => $produtos,
            'categorias' => $categorias,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'total' => $total,
            'limit' => $limit
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

        try {
            // converte a categoria
            $categoria = ProdutoCategoria::tryFrom($data['categoria']);

            if (!$categoria) {
                throw new \InvalidArgumentException("Categoria inválida!");
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

            $this->addFlash('success', 'Produto criado com sucesso!');

            return $this->json([
                'message' => 'Produto criado com sucesso!',
                'status' => 'success'
            ], 201);
        } catch (\Exception $e) {
            $this->addFlash('error', 'Erro ao criar produto: ' . $e->getMessage());
            
            return $this->json([
                'message' => 'Erro ao criar produto: ' . $e->getMessage(),
                'status' => 'error'
            ], 400);
        }
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
