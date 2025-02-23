<?php

namespace App\Form;

use App\Entity\Produto;
use App\Enum\ProdutoCategoria;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProdutoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nome', TextType::class, [
                'required' => true,
                'attr' => ['placeholder' => 'Digite o nome do produto']
            ])
            ->add('categoria', EnumType::class, [
                'class' => ProdutoCategoria::class,
                'required' => true,
                'choice_label' => fn ($choice) => $choice->getDescription(),
                'placeholder' => 'Selecione a categoria'
            ])
            ->add('descricao', TextareaType::class, [
                'required' => true,
                'attr' => [
                    'rows' => 4,
                    'placeholder' => 'Escreva a descrição do produto'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Produto::class,
            'csrf_protection' => false,
        ]);
    }
} 