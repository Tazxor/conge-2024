<?php 
// src/Form/CommentaireUserType.php
namespace App\Form;

use App\Entity\Commentaire;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class CommentaireUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('reponseUser', TextType::class, [
                'label' => 'Votre commentaire',
            ])
            ->add('reponseAdmin', TextType::class, [
                'label' => 'Réponse de l\'administrateur',
                'disabled' => true, // Rendre le champ non modifiable pour l'utilisateur
            ]);
            // ->add('conge');
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Commentaire::class,
        ]);
    }
}