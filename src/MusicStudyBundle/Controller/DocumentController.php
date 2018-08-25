<?php

namespace MusicStudyBundle\Controller;

use MusicStudyBundle\Entity\Document;
use MusicStudyBundle\Form\DocumentType;
use MusicStudyBundle\Service\DocumentService;

use Vich\UploaderBundle\Templating\Helper\UploaderHelper;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\BrowserKit\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;


class DocumentController extends Controller
{
    /**
     * @var DocumentService
     */
    private $documentService;

    /**
     * @var UploaderHelper
     */
    private $uploadHelper;

    /**
     * @param DocumentService $documentService
     * @param UploaderHelper $uploaderHelper
     */
    public function __construct(DocumentService $documentService, UploaderHelper $uploaderHelper)
    {
        $this->documentService = $documentService;
        $this->uploadHelper = $uploaderHelper;
    }

    /**
     * @Route("/doc/list", name="list_document")
     * @Template("MusicStudyBundle/Document/list.html.twig")
     */
    public function listAction(Request $request)
    {
        $documents = $this->documentService->getDocuments();
        $document = new Document($displayable = true);
        $form = $this->createForm(DocumentType::class, $document);
        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $this->documentService->createDocument($document);
                return $this->redirectToRoute('list_document');
            }
        }

        return array(
            'form' => $form->createView(),
            'documents'=>$documents
        );
    }

    /**
     * @Route("/doc/user/{id}", name="user_document")
     * @Template("MusicStudyBundle/Document/list.html.twig")
     */
    public function listDocumentsPersoAction(Request $request, $id)
    {
        return array(
            'documents'=>$this->documentService->getDocumentsByUser($request->get('index'), $request->get('count'), $id),
            'form'=>null
        );
    }

    /**
     * @Route("/doc/delete/{id}", name="delete_document")
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Request $request, $id)
    {
        $this->documentService->deleteDocument($id);

        return $this->redirectToRoute('list_document');
    }

    /**
     * @Route("/doc/download", name="download_document")
     */
    public function downloadImageAction(Request $request)
    {
        $document = $this->documentService->getDocumentById($request->get('idFile'));

        //FIXME: Uncomment to check rights
//        if($document->getUtilisateur()!= null && $document->getUtilisateur()->getId() == $userId){
//            return $downloadHandler->downloadObject($document, $fileField = 'file');
//        }

//        $helper = $this->container->get('vich_uploader.templating.helper.uploader_helper');
        $path = $this->uploadHelper->asset($document, 'file');

        return  new JsonResponse(array('path' => $path));
    }
}
