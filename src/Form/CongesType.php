<?php

namespace App\Form;

use App\Entity\Conges;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CongesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('date_debut_conge')
            ->add('date_fin_conge')
            // Ajoutez ici le formulaire de commentaire en tant que collection
            // ->add('commentaires', CollectionType::class, [
            //     'entry_type' => CommentaireUserType::class,
            //     'entry_options' => ['label' => false],
            //     'allow_add' => true,
            //     'by_reference' => false,
            //     'required' => false,
            // ])
        ;

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Conges::class,
        ]);
    }
}
