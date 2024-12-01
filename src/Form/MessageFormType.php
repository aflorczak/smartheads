<?php

namespace App\Form;

use App\Entity\Message;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class MessageFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Imię nie może być puste.']),
                    ]
            ])
            ->add('pesel', TextType::class, [
                'constraints' => [
                    new Assert\NotBlank(['message' => 'PESEL nie może być pusty.']),
                    new Assert\Regex([
                        'pattern' => '/^\d{11}$/',
                        'message' => 'PESEL musi składać się z 11 cyfr.',
                    ]),
                    new Assert\Callback([$this, 'validatePesel']),
                ]
            ])
            ->add('email', EmailType::class, [
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Adres e-mail jest wymagany.',
                    ]),
                    new Assert\Email([
                        'message' => 'Proszę podać poprawny adres e-mail.',
                    ]),
                ]
            ])
            ->add('content');
    }

    /**
     * Custom validation logic for PESEL
     */
    public function validatePesel($pesel, \Symfony\Component\Validator\Context\ExecutionContextInterface $context)
    {
        if (!$this->isPeselValid($pesel)) {
            $context->buildViolation('Podano nieprawidłowy numer PESEL.')
                ->addViolation();
        }
    }

    /**
     * Helper function to validate PESEL
     */
    private function isPeselValid(string $pesel): bool
    {
        // PESEL length check
        if (strlen($pesel) !== 11 || !ctype_digit($pesel)) {
            return false;
        }

        // Extract digits and weights
        $weights = [1, 3, 7, 9, 1, 3, 7, 9, 1, 3];
        $checksum = 0;

        // Calculate checksum
        for ($i = 0; $i < 10; $i++) {
            $checksum += $weights[$i] * (int)$pesel[$i];
        }

        // Validate checksum
        $lastDigit = (10 - ($checksum % 10)) % 10;

        return $lastDigit === (int)$pesel[10];
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Message::class,
        ]);
    }
}
