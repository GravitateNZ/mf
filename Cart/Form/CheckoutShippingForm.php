<?php

namespace MillenniumFalcon\Cart\Form;

use MillenniumFalcon\Cart\Form\Constraints\NotBlankIfRequired;
use MillenniumFalcon\Cart\Service\CartService;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class CheckoutShippingForm extends AbstractType
{

    public function getBlockPrefix()
    {
        return 'cart_shipping';
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $request = $options['request'];
        $connection = $options['connection'];
        /** @var CartService $cartService */
        $cartService = $options['cartService'];

        $countries = array_filter(array_map(function ($itm) {
            return $itm ? $itm->getTitle() : null;
        }, $cartService->getDeliverableCountries()));
        $countries = array_combine(array_values($countries), array_values($countries));

        $builder
            ->add('email', EmailType::class, [
                'label' => 'Your email address',
                'required' => true,
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Email(),
                ]
            ])
            ->add('isPickup', ChoiceType::class, [
                'required' => true,
                'expanded' => true,
                'choices' => [
                    'Pick-up' => 1,
                    'Delivery' => 2,
                ],
                'constraints' => [
                    new Assert\NotBlank(),
                ]
            ])
            ->add('pickupFirstName', TextType::class, [
                'label' => 'First name',
                'required' => true,
                'constraints' => [
                    new NotBlankIfRequired([
                        'callback' => function ($request) {
                            $data = $request->get($this->getBlockPrefix());
                            return isset($data['isPickup']) && $data['isPickup'] == 1 ? 1 : 0;
                        },
                        'request' => $request,
                    ]),
                ]
            ])
            ->add('pickupLastName', TextType::class, [
                'label' => 'Last name',
                'required' => true,
                'constraints' => [
                    new NotBlankIfRequired([
                        'callback' => function ($request) {
                            $data = $request->get($this->getBlockPrefix());
                            return isset($data['isPickup']) && $data['isPickup'] == 1 ? 1 : 0;
                        },
                        'request' => $request,
                    ]),
                ]
            ])
            ->add('pickupPhone', TelType::class, [
                'label' => 'Mobile',
                'required' => true,
                'constraints' => [
                    new NotBlankIfRequired([
                        'callback' => function ($request) {
                            $data = $request->get($this->getBlockPrefix());
                            return isset($data['isPickup']) && $data['isPickup'] == 1 ? 1 : 0;
                        },
                        'request' => $request,
                    ]),
                ]
            ])
            ->add('shippingFirstName', TextType::class, [
                'label' => 'First name',
                'required' => true,
                'constraints' => [
                    new NotBlankIfRequired([
                        'callback' => function ($request) {
                            $data = $request->get($this->getBlockPrefix());
                            return isset($data['isPickup']) && $data['isPickup'] == 2 ? 1 : 0;
                        },
                        'request' => $request,
                    ]),
                ]
            ])
            ->add('shippingLastName', TextType::class, [
                'label' => 'Last name',
                'required' => true,
                'constraints' => [
                    new NotBlankIfRequired([
                        'callback' => function ($request) {
                            $data = $request->get($this->getBlockPrefix());
                            return isset($data['isPickup']) && $data['isPickup'] == 2 ? 1 : 0;
                        },
                        'request' => $request,
                    ]),
                ]
            ])
            ->add('shippingPhone', TelType::class, [
                'label' => 'Mobile',
                'required' => true,
                'constraints' => [
                    new NotBlankIfRequired([
                        'callback' => function ($request) {
                            $data = $request->get($this->getBlockPrefix());
                            return isset($data['isPickup']) && $data['isPickup'] == 2 ? 1 : 0;
                        },
                        'request' => $request,
                    ]),
                ]
            ])
            ->add('shippingApartmentNo', TextType::class, [
                'label' => 'Apartment No.',
                'required' => true,
                'constraints' => [
                ]
            ])
            ->add('shippingAddress', TextType::class, [
                'label' => 'Address',
                'required' => true,
                'constraints' => [
                    new NotBlankIfRequired([
                        'callback' => function ($request) {
                            $data = $request->get($this->getBlockPrefix());
                            return isset($data['isPickup']) && $data['isPickup'] == 2 ? 1 : 0;
                        },
                        'request' => $request,
                    ]),
                ]
            ])
            ->add('shippingCity', TextType::class, [
                'label' => 'City',
                'required' => true,
                'constraints' => [
                    new NotBlankIfRequired([
                        'callback' => function ($request) {
                            $data = $request->get($this->getBlockPrefix());
                            return isset($data['isPickup']) && $data['isPickup'] == 2 ? 1 : 0;
                        },
                        'request' => $request,
                    ]),
                ]
            ])
            ->add('shippingPostcode', TextType::class, [
                'label' => 'Postcode',
                'required' => true,
                'constraints' => [
                    new NotBlankIfRequired([
                        'callback' => function ($request) {
                            $data = $request->get($this->getBlockPrefix());
                            return isset($data['isPickup']) && $data['isPickup'] == 2 ? 1 : 0;
                        },
                        'request' => $request,
                    ]),
                ]
            ]);

        if (getenv('SHIPPING_PRICE_MODE') == 1) {
            $builder
                ->add('shippingState', TextType::class, [
                    'label' => 'Region',
                    'required' => true,
                    'constraints' => [
                        new NotBlankIfRequired([
                            'callback' => function ($request) {
                                $data = $request->get($this->getBlockPrefix());
                                return isset($data['isPickup']) && $data['isPickup'] == 2 ? 1 : 0;
                            },
                            'request' => $request,
                        ]),
                    ]
                ])
                ->add('shippingCountry', ChoiceType::class, [
                    'label' => 'Country',
                    'required' => true,
                    'choices' => $countries,
                    'constraints' => [
                        new NotBlankIfRequired([
                            'callback' => function ($request) {
                                $data = $request->get($this->getBlockPrefix());
                                return isset($data['isPickup']) && $data['isPickup'] == 2 ? 1 : 0;
                            },
                            'request' => $request,
                        ]),
                    ]
                ]);
        } else if (getenv('SHIPPING_PRICE_MODE') == 2) {
            $builder
                ->add('shippingCountry', ChoiceType::class, [
                    'label' => 'Country',
                    'required' => true,
                    'choices' => $countries,
                    'constraints' => [
                        new NotBlankIfRequired([
                            'callback' => function ($request) {
                                $data = $request->get($this->getBlockPrefix());
                                return isset($data['isPickup']) && $data['isPickup'] == 2 ? 1 : 0;
                            },
                            'request' => $request,
                        ]),
                    ]
                ]);
        }

        $builder
            ->add('shippingId', TextType::class, [
                'label' => 'Delivery options',
                'required' => true,
                'constraints' => [
                    new NotBlankIfRequired([
                        'callback' => function ($request) {
                            $data = $request->get($this->getBlockPrefix());
                            return isset($data['isPickup']) && $data['isPickup'] == 2 ? 1 : 0;
                        },
                        'request' => $request,
                    ]),
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);
        $resolver->setDefaults([
            'request' => null,
            'connection' => null,
            'cartService' => null,
        ]);
    }
}
