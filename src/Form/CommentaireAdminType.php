<?php
// src/Form/CommentaireAdminType.php
namespace App\Form;

use App\Entity\Commentaire;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class CommentaireAdminType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('reponse_user', TextType::class, [
                'label' => 'Commentaire de l\'utilisateur',
                'disabled' => true, // Rendre le champ non modifiable pour l'administrateur
            ])
            ->add('reponse_admin', TextType::class, [
                'label' => 'Votre réponse',
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
