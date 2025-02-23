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
use App\Form\ProdutoType;

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
            // Buscar todas as categorias que correspondem à pesquisa
            $categoriasMatchingSearch = array_filter(
                ProdutoCategoria::cases(),
                fn($categoria) => str_contains(
                    strtolower($categoria->getDescription()),
                    strtolower($search)
                )
            );
            
            $categoriaValues = array_map(fn($cat) => $cat->value, $categoriasMatchingSearch);
            
            if (!empty($categoriaValues)) {
                $qb->where('LOWER(p.nome) LIKE LOWER(:search) OR p.categoria IN (:categorias)')
                   ->setParameter('search', '%' . $search . '%')
                   ->setParameter('categorias', $categoriaValues);
            } else {
                $qb->where('LOWER(p.nome) LIKE LOWER(:search)')
                   ->setParameter('search', '%' . $search . '%');
            }
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
        $produto = new Produto();
        $form = $this->createForm(ProdutoType::class, $produto);
        
        try {
            $data = json_decode($request->getContent(), true);
            $form->submit($data);

            if ($form->isValid()) {
                $produto->setQuantidade(0);
                $produto->setCreatedAt(new \DateTimeImmutable('now', new DateTimeZone('America/Sao_Paulo')));
                $produto->setUpdatedAt(new \DateTimeImmutable('now', new DateTimeZone('America/Sao_Paulo')));

                $entityManager->persist($produto);
                $entityManager->flush();

                return $this->json([
                    'message' => 'Produto criado com sucesso!',
                    'status' => 'success'
                ], 201);
            }

            // Se o formulário não for válido, retorna os erros
            $errors = [];
            foreach ($form->getErrors(true) as $error) {
                $errors[] = $error->getMessage();
            }

            return $this->json([
                'message' => 'Erro de validação',
                'errors' => $errors,
                'status' => 'error'
            ], 400);

        } catch (\Exception $e) {
            return $this->json([
                'message' => 'Erro ao criar produto: ' . $e->getMessage(),
                'status' => 'error'
            ], 400);
        }
    }

    #[Route('/produto/{id}', name: 'produto_update', methods: ['PUT'])]
    public function update(Request $request, EntityManagerInterface $entityManager, int $id): JsonResponse
    {
        $produto = $entityManager->getRepository(Produto::class)->find($id);

        if (!$produto) {
            return $this->json([
                'message' => 'Produto não encontrado',
                'status' => 'error'
            ], 404);
        }

        $form = $this->createForm(ProdutoType::class, $produto);
        
        try {
            $data = json_decode($request->getContent(), true);
            $form->submit($data);

            if ($form->isValid()) {
                $produto->setUpdatedAt(new \DateTimeImmutable('now', new DateTimeZone('America/Sao_Paulo')));
                $entityManager->flush();

                return $this->json([
                    'message' => 'Produto atualizado com sucesso!',
                    'status' => 'success'
                ]);
            }

            return $this->json([
                'message' => 'Erro de validação',
                'errors' => $form->getErrors(true),
                'status' => 'error'
            ], 400);

        } catch (\Exception $e) {
            return $this->json([
                'message' => 'Erro ao atualizar produto: ' . $e->getMessage(),
                'status' => 'error'
            ], 400);
        }
    }
}
