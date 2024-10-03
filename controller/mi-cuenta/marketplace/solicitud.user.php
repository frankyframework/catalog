<?php
use Catalog\Form\UserMarketplaceForm;
use Catalog\model\CatalogUsersReviewsModel;
use Catalog\entity\CatalogUsersReviewsEntity;
use Franky\Haxor\Tokenizer;

$CatalogUsersReviewsModel = new CatalogUsersReviewsModel();
$CatalogUsersReviewsEntity = new CatalogUsersReviewsEntity();
$Tokenizer = new Tokenizer();

$userForm = new UserMarketplaceForm('frmusermarketplace');
$userForm->setMobile($Mobile_detect->isMobile());
$CatalogUsersReviewsEntity->status(0);
$CatalogUsersReviewsEntity->parent_id($MySession->GetVar('id'));
$result	 = $CatalogUsersReviewsModel->getData($CatalogUsersReviewsEntity->getArrayCopy());

$dataUM = $CatalogUsersReviewsModel->getRows();

$userForm = new UserMarketplaceForm('frmusermarketplace');
$userForm->setMobile($Mobile_detect->isMobile());
$userForm->setAtributoInput("id", "value",$Tokenizer->token('new_user_marketplace',$dataUM['id']));