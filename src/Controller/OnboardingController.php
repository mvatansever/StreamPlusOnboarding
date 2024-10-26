<?php

// src/Controller/OnboardingController.php
namespace App\Controller;

use App\Form\UserInfoType;
use App\Form\AddressType;
use App\Form\PaymentType;
use App\Service\OnboardingService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
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

        if ($request->isXmlHttpRequest()) { // Check if it's an AJAX request
            if ($form->isSubmitted() && $form->isValid()) {
                // Store user info temporarily in session
                $data = $form->getData();
                $request->getSession()->set('user_info', $data);

                return new JsonResponse([
                    'success' => true,
                    'redirectUrl' => $this->generateUrl('onboarding_address') // Redirect to the next step
                ]);
            }

            // If form validation fails, return errors as JSON
            $errors = [];
            foreach ($form->getErrors(true) as $error) {
                $errors[$error->getOrigin()->getName()] = $error->getMessage();
            }

            return $this->json(['success' => false, 'errors' => $errors], Response::HTTP_BAD_REQUEST);
        }

        // For non-AJAX requests, render the template as usual
        return $this->render('onboarding/user_info.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/onboarding/address', name: 'onboarding_address')]
    public function address(Request $request): Response
    {
        $form = $this->createForm(AddressType::class);
        $form->handleRequest($request);

        if ($request->isXmlHttpRequest()) { // Check if it's an AJAX request
            if ($form->isSubmitted() && $form->isValid()) {
                // Store address data in session or handle as needed
                $data = $form->getData();
                $request->getSession()->set('address', $data);

                return new JsonResponse([
                    'success' => true,
                    'redirectUrl' => $this->generateUrl('onboarding_confirmation')
                ]);
            }

            // Return validation errors if form submission fails
            $errors = [];
            foreach ($form->getErrors(true) as $error) {
                $errors[$error->getOrigin()->getName()] = $error->getMessage();
            }

            return new JsonResponse(['success' => false, 'errors' => $errors], Response::HTTP_BAD_REQUEST);
        }

        // For non-AJAX requests, render the template as usual
        return $this->render('onboarding/address.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/onboarding/payment', name: 'onboarding_payment')]
    public function payment(Request $request): Response
    {
        $form = $this->createForm(PaymentType::class);
        $form->handleRequest($request);
        if ($request->isXmlHttpRequest()) {
            if ($form->isSubmitted() && $form->isValid()) {
                // Save payment data temporarily in the session
                $request->getSession()->set('payment', $form->getData());

                return $this->json([
                    'success' => true,
                    'message' => 'Payment information saved successfully!',
                    'redirectUrl' => $this->generateUrl('onboarding_confirmation')
                ]);
            }

            // If not valid, return errors as JSON
            $errors = [];
            foreach ($form->getErrors(true) as $error) {
                $errors[$error->getOrigin()->getName()] = $error->getMessage();
            }

            return $this->json(['success' => false, 'errors' => $errors], Response::HTTP_BAD_REQUEST);
        }

        // For non-AJAX requests, render the page normally
        return $this->render('onboarding/payment.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    private function savePaymentData(array $paymentData)
    {
        // Create a new Payment entity (assuming you have this entity)
        $payment = new Payment();
        $payment->setCardNumber($paymentData['cardNumber']);
        $payment->setExpirationDate($paymentData['expirationDate']);
        $payment->setCvv($paymentData['cvv']);

        // Persist and flush to save to the database
        $this->entityManager->persist($payment);
        $this->entityManager->flush();
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
