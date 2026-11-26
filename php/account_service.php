<?php
/** Save all registration rows atomically. A failed INSERT rolls back every write. */
function createAccount(mysqli $db, array $account): int
{
    $db->begin_transaction();
    try {
        $contact = $db->prepare('INSERT INTO Contacts
            (first_name, last_name, email, phone, street_1, street_2, city, state_code, post_code)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)');
        $contact->bind_param('sssssssss', $account['first_name'], $account['last_name'],
            $account['email'], $account['phone'], $account['street'],
            $account['street_additional'], $account['city'], $account['state'], $account['zip']);
        $contact->execute();
        $contactId = $db->insert_id;
        $contact->close();

        $roleId = 2;
        $user = $db->prepare('INSERT INTO Users (contact_id, role_id) VALUES (?, ?)');
        $user->bind_param('ii', $contactId, $roleId);
        $user->execute();
        $userId = $db->insert_id;
        $user->close();

        $hash = password_hash($account['password'], PASSWORD_DEFAULT);
        $credential = $db->prepare('INSERT INTO Credentials (user_id, username, password_salted) VALUES (?, ?, ?)');
        $credential->bind_param('iss', $userId, $account['username'], $hash);
        $credential->execute();
        $credential->close();
        $db->commit();
        return $userId;
    } catch (Throwable $error) {
        $db->rollback();
        throw $error;
    }
}
