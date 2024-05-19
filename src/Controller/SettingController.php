<?php

namespace App\Controller;

use App\Entity\Setting;
use App\Form\SettingType;
use App\Repository\SettingRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/setting')]
class SettingController extends AbstractController
{
    #[Route('/new', name: 'app_setting_new', methods: ['GET', 'POST'])]
    public function new(Request $request, SettingRepository $settingRepository): Response
    {
        $setting = new Setting();
        $form = $this->createForm(SettingType::class, $setting);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $settingRepository->add($setting, true);
            return $this->redirectToRoute('app_setting_show', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('setting/new.html.twig', [
            'setting' => $setting,
            'form' => $form,
        ]);
    }

    #[Route('/', name: 'app_setting_show', methods: ['GET'])]
    public function show(SettingRepository $settingRepository): Response
    {
        $setting = $settingRepository->findOneBy([]);

        if(!($setting instanceof Setting)) {
            return $this->redirectToRoute('app_setting_new');
        }

        return $this->render('setting/show.html.twig', [
            'setting' => $setting
        ]);
    }

    #[Route('/{id}/edit', name: 'app_setting_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Setting $setting, SettingRepository $settingRepository): Response
    {
        $form = $this->createForm(SettingType::class, $setting);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $settingRepository->add($setting, true);
            return $this->redirectToRoute('app_setting_show', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('setting/edit.html.twig', [
            'setting' => $setting,
            'form' => $form,
        ]);
    }
}
