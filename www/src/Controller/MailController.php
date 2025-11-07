<?php

namespace App\Controller;

use App\Service\FileManager;
use App\Repository\ClientRepository;
use App\Repository\SessionFormationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpKernel\KernelInterface;

class MailController extends AbstractController
{
    #[Route('/mail/client/{id}/note', name: 'mail_client_note')]
    public function showPdfForClient(int $id, ClientRepository $clientRepo, SessionFormationRepository $sessionRepo, FileManager $fm, KernelInterface $kernel): Response
    {
        $client = $clientRepo->find($id);
        if (!$client) {
            throw $this->createNotFoundException('Client non trouvé');
        }

    $sessions = $sessionRepo->findByClient($client);

        $logoPath = $kernel->getProjectDir().'/public/img/logo.jpg';
        if (file_exists($logoPath) && is_readable($logoPath)) {
            $logo = 'data:image/jpg;base64,' . base64_encode(file_get_contents($logoPath));
        } else {
            $logo = '';
        }

        $pdf = $fm->renderPdfFromTemplate('mail/session_note.pdf.twig', [
            'client' => $client,
            'sessions' => $sessions,
            'logo' => $logo,
            'sessions' => array_map(function($s){ return [
                'formation' => $s->getIdFormation(),
                'dateDebut' => $s->getDateDebut(),
                'dateFin' => $s->getDateFin(),
                'projet' => $s->getIdProjet(),
                'cout' => $s->getCout()
            ]; }, $sessions)
        ]);

        $response = new StreamedResponse(function() use ($pdf) { echo $pdf; });
        $response->headers->set('Content-Type', 'application/pdf');
        $response->headers->set('Content-Disposition', 'inline; filename="session_note_'.$id.'.pdf"');

        return $response;
    }

    #[Route('/mail/client/{id}/send-note', name: 'mail_client_send_note')]
    public function sendNoteToClient(int $id, ClientRepository $clientRepo, SessionFormationRepository $sessionRepo, FileManager $fm, KernelInterface $kernel, MailerInterface $mailer): Response
    {
        $client = $clientRepo->find($id);
        if (!$client) {
            throw $this->createNotFoundException('Client non trouvé');
        }

    $sessions = $sessionRepo->findByClient($client);

        $logoPath = $kernel->getProjectDir().'/public/img/logo.jpg';
        if (file_exists($logoPath) && is_readable($logoPath)) {
            $logo = 'data:image/jpg;base64,' . base64_encode(file_get_contents($logoPath));
        } else {
            $logo = '';
        }

        $pdf = $fm->renderPdfFromTemplate('mail/session_note.pdf.twig', [
            'client' => $client,
            'sessions' => array_map(function($s){ return [
                'formation' => $s->getIdFormation(),
                'dateDebut' => $s->getDateDebut(),
                'dateFin' => $s->getDateFin(),
                'projet' => $s->getIdProjet(),
                'cout' => $s->getCout()
            ]; }, $sessions),
            'logo' => $logo
        ]);

        $email = (new Email())
            ->from('no-reply@lesforgesduweb.fr')
            ->to($client->getEmail())
            ->subject('Récapitulatif sessions')
            ->text('Veuillez trouver en pièce jointe le récapitulatif des sessions.')
            ->html('<p>Bonjour,</p><p>Veuillez trouver en pièce jointe le récapitulatif de vos sessions.</p>');

        $email->attach($pdf, 'recapitulatif-session-'.$id.'.pdf', 'application/pdf');

        $mailer->send($email);

        $this->addFlash('success', 'Email envoyé (vérifiez Mailhog)');

        return $this->redirectToRoute('app_home');
    }
}
