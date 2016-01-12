<?php
/**
 * Page de confirmation d'inscription, est affichee une fois que
 * le compte a ete cree.
 */

AbstractView::getView('persists/head');
echo "Un email vous a été envoyé, rendez vous sur votre boite mail pour valider l'inscription";
AbstractView::getView('persists/end');


