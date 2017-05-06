<?php

/***********************************************************************************************
 * Angular->php standard web service - Full native php web service Angular friendly
 *   AjaxController.php Controller for all Ajax request
 * Copyright 2016 Thomas DUPONT
 * MIT License
 ************************************************************************************************/

namespace bin\controllers;

use bin\models\{Cart, Order, Node, User};
use bin\services\Upload;

/**
* @pattern Command, VMC
* All action is in private and in uppercase. The whitelist are checking the auth actions
*/
final class AjaxController extends Controller implements APIInterface {

    public function __construct()
    {
        parent::__construct();
    }

    /**
    * MUST be implemented
    *
    */
    public function execute ()
    : string
    {
        $funct = "_".strtoupper($this->request->action);
        $functWhiteList = [
          '_SENDCONTACT',
          '_LOGIN',
          '_GETCART',
          '_GETHOME',
          '_UPLOAD',
          '_CHECKUSER',
          '_REGISTER',
          '_DISCONNECT',
          '_DELETENODE',
          '_CREATEFOLDER'
        ];
        return in_array($funct, $functWhiteList) ? $this->$funct() : json_encode(['success' => false]);
    }

    private function _SENDCONTACT ()
    : string
    {
        return $this->request->text;
    }

    private function _LOGIN ()
    : string
    {
        return json_encode(
            (new User())->login(
                (string) $this->request->login,
                (string) $this->request->password
            )
        );
    }

    private function _GETCART ()
    : string
    {
        $cart = new Cart($this->mysql);
        $myCart = $cart->getCart();
        //.....
    }
    private function _GETHOME ()
    : string
    {
        $node = new Node();
        return json_encode($node->getNodes());
    }

    private function _UPLOAD ()
    : string
    {
        return json_encode(
            Upload::checkFile(
                $this->request->file,
                $this->request->filename
            )->moveFile($this->request->pNodeId)
        );
    }

    private function _CREATEFOLDER ()
    : string
    {
        return json_encode(
            (new Node())->setNode($this->request->nodeId, $this->request->name, true)
        );
    }

    private function _DELETENODE ()
    : string
    {
        return json_encode((new Node())->unsetNode($this->request->nodeId));
    }

    private function _CHECKUSER ()
    : string
    {
        return json_encode((new User())->checkUser());
    }

    private function _REGISTER ()
    : string
    {
        $createUser = (new User())->register(
            (string) $this->request->login, (string) $this->request->password
        );
        return $createUser['success'] ? json_encode($createUser) : "Cet utilisateur éxiste déjà";
    }

    private function _DISCONNECT ()
    : string
    {
        return json_encode((new User())->disconnect());
    }
}
