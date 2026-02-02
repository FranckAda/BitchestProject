<?php

namespace App\Form;

use App\Entity\CryptoCurrency;
use App\Entity\Transaction;
use App\Entity\Wallet;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TransactionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('type')
            ->add('quantity')
            ->add('price')
            ->add('date')
            ->add('wallet', EntityType::class, [
                'class' => Wallet::class,
                'choice_label' => 'id',
            ])
            ->add('cryptoCurrency', EntityType::class, [
                'class' => CryptoCurrency::class,
                'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Transaction::class,
        ]);
    }
}
