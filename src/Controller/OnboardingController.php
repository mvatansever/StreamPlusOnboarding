<?php

namespace App\Controller;

use App\Form\AddressRequestType;
use App\Form\PaymentRequestType;
use App\Form\UserInfoRequestType;
use App\Request\AddressRequest;
use App\Request\PaymentRequest;
use App\Request\SubmitRequest;
use App\Request\UserInfoRequest;
use App\Service\Handlers\AddressHandler;
use App\Service\Handlers\HandlerResponse;
use App\Service\Handlers\PaymentHandler;
use App\Service\Handlers\SubmitHandler;
use App\Service\Handlers\UserInfoHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OnboardingController extends AbstractController
{
    #[Route('/onboarding/user-info', name: 'onboarding_user_info')]
    public function userInfo(UserInfoHandler $handler, UserInfoRequest $userInfoRequest, Request $request, FormFactoryInterface $formFactory): Response
    {
        return $this->handleForm($handler, $userInfoRequest, $request, $formFactory, UserInfoRequestType::class, 'onboarding/user_info.html.twig');
    }

    #[Route('/onboarding/address', name: 'onboarding_address')]
    public function address(AddressHandler $handler, AddressRequest $addressRequest, Request $request, FormFactoryInterface $formFactory): Response
    {
        return $this->handleForm($handler, $addressRequest, $request, $formFactory, AddressRequestType::class, 'onboarding/address.html.twig');
    }

    #[Route('/onboarding/payment', name: 'onboarding_payment')]
    public function payment(PaymentHandler $handler, PaymentRequest $paymentRequest, Request $request, FormFactoryInterface $formFactory): Response
    {
        return $this->handleForm($handler, $paymentRequest, $request, $formFactory, PaymentRequestType::class, 'onboarding/payment.html.twig');
    }

    #[Route('/onboarding/submit', name: 'onboarding_submit', methods: ['POST'])]
    public function submit(SubmitHandler $handler, SubmitRequest $submitRequest): Response
    {
        $resp = $handler->handle($submitRequest);

        return $resp->isSuccess() ? $this->redirectToRoute($resp->getRedirectUrl()) : $this->handleResponse($resp);
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

    private function handleForm($handler, $requestData, Request $request, FormFactoryInterface $formFactory, ?string $requestTypeClass, string $template): Response
    {
        if ($request->isXmlHttpRequest()) {
            return $this->handleResponse($handler->handle($requestData));
        }

        $form = $formFactory->create($requestTypeClass, $requestData);

        return $this->render($template, [
            'form' => $form->createView(),
        ]);
    }

    private function handleResponse(HandlerResponse $handlerResponse): JsonResponse
    {
        return $handlerResponse->isSuccess() ?
            $this->json([
                'success' => $handlerResponse->isSuccess(),
                'redirectUrl' => $this->generateUrl($handlerResponse->getRedirectUrl()),
            ]) : $this->json(['success' => false, 'errors' => $handlerResponse['errors']], Response::HTTP_BAD_REQUEST);
    }
}
