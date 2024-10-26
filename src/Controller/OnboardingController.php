<?php

// src/Controller/OnboardingController.php
namespace App\Controller;

use App\Form\UserInfoType;
use App\Form\AddressType;
use App\Form\PaymentType;
use App\Service\OnboardingService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OnboardingController extends AbstractController
{
    public function __construct(private readonly OnboardingService $onboardingService){}

    #[Route('/onboarding/user-info', name: 'onboarding_user_info')]
    public function userInfo(Request $request): Response
    {
        $form = $this->createForm(UserInfoType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Save data to session or database and redirect to the next step
            $data = $form->getData();
            $request->getSession()->set('user_info', $data);

            return $this->redirectToRoute('onboarding_address');
        }

        return $this->render('onboarding/user_info.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/onboarding/address', name: 'onboarding_address')]
    public function address(Request $request): Response
    {
        $form = $this->createForm(AddressType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $request->getSession()->set('address', $data);

            // Redirect based on subscription type
            $userInfo = $request->getSession()->get('user_info');
            if ($userInfo['subscriptionType'] === 'premium') {
                return $this->redirectToRoute('onboarding_payment');
            }
            return $this->redirectToRoute('onboarding_confirmation');
        }

        return $this->render('onboarding/address.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/onboarding/payment', name: 'onboarding_payment')]
    public function payment(Request $request): Response
    {
        $form = $this->createForm(PaymentType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $request->getSession()->set('payment', $data);

            return $this->redirectToRoute('onboarding_confirmation');
        }

        return $this->render('onboarding/payment.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/onboarding/submit', name: 'onboarding_submit', methods: ['POST'])]
    public function submit(Request $request): Response
    {
        // Fetch data from the session
        $userInfo = $request->getSession()->get('user_info');
        $address = $request->getSession()->get('address');
        $payment = $request->getSession()->get('payment');

        $this->onboardingService->saveOnboardingData($userInfo,$address,$payment);

        // Clear the session after saving the data
        $request->getSession()->clear();

        // Redirect to a completion or thank-you page
        return $this->redirectToRoute('onboarding_complete');
    }

    #[Route('/onboarding/confirmation', name: 'onboarding_confirmation')]
    public function confirmation(Request $request): Response
    {
        $userInfo = $request->getSession()->get('user_info');
        $address = $request->getSession()->get('address');
        $payment = $request->getSession()->get('payment');

        return $this->render('onboarding/confirmation.html.twig', [
            'userInfo' => $userInfo,
            'address' => $address,
            'payment' => $payment,
        ]);
    }

    #[Route('/onboarding/complete', name: 'onboarding_complete')]
    public function complete(): Response
    {
        return $this->render('onboarding/complete.html.twig');
    }
}
