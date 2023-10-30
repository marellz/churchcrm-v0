<?php

use ChurchCRM\dto\Cart;

$app->group('/cart', function () use ($app) {

    $app->get('/', function ($request, $response, $args) {
        return $response->withJson(['PeopleCart' => $_SESSION['aPeopleCart']]);
    });

    $app->post('/', function ($request, $response, $args) {
        $cartPayload = (object)$request->getParsedBody();
        if (isset($cartPayload->Persons) && count($cartPayload->Persons) > 0) {
            Cart::AddPersonArray($cartPayload->Persons);
        } elseif (isset($cartPayload->Family)) {
            Cart::AddFamily($cartPayload->Family);
        } elseif (isset($cartPayload->Group)) {
            Cart::AddGroup($cartPayload->Group);
        } else {
            throw new \Exception(gettext("POST to cart requires a Persons array, FamilyID, or GroupID"), 500);
        }
        return $response->withJson(['status' => "success"]);
    });

    $app->post('/emptyToGroup', function ($request, $response, $args) {
        $cartPayload = (object)$request->getParsedBody();
        Cart::EmptyToGroup($cartPayload->groupID, $cartPayload->groupRoleID);
        return $response->withJson([
            'status' => "success",
            'message' => gettext('records(s) successfully added to selected Group.')
        ]);
    });

    $app->post('/removeGroup', function ($request, $response, $args) {
        $cartPayload = (object)$request->getParsedBody();
        Cart::RemoveGroup($cartPayload->Group);
        return $response->withJson([
            'status' => "success",
            'message' => gettext('records(s) successfully deleted from the selected Group.')
        ]);
    });


    /**
     * delete. This will empty the cart
     */
    $app->delete('/', function ($request, $response, $args) {

        $cartPayload = (object)$request->getParsedBody();
        if (isset($cartPayload->Persons) && count($cartPayload->Persons) > 0) {
            Cart::RemovePersonArray($cartPayload->Persons);
        } else {
            $sMessage = gettext('Your cart is empty');
            if (sizeof($_SESSION['aPeopleCart']) > 0) {
                $_SESSION['aPeopleCart'] = [];
                $sMessage = gettext('Your cart has been successfully emptied');
            }
        }
        return $response->withJson([
            'status' => "success",
            'message' => $sMessage
        ]);
    });
});
