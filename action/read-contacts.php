<?php

require "../conn.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    session_start();

    $query = "SELECT * FROM uid_" . $_SESSION["login"]["uid"];
    $res = mysqli_query($conn, $query);
    $totalpage = mysqli_num_rows($res);
    $limit = 5;

    $page = $_POST["page"];
    $offset = ($page -1) * $limit;


    if (isset($_POST["search"]) && trim($_POST["search"]) != "") {
        $search = $_POST["search"];
        $query = "SELECT * FROM uid_" . $_SESSION["login"]["uid"] . " WHERE name like '%$search%' ORDER BY name";
    } else {
        $query = "SELECT * FROM uid_" . $_SESSION["login"]["uid"] . " ORDER BY name LIMIT $offset , $limit";
    }


    $response = "<tr class='table-header'>
                <th></th>
                <th>Name</th>
                <th>Mobile No</th>
                <th>Contact Details</th>
                <th>Edit</th>
                <th>Delete</th>
            </tr>";

    if ($res = mysqli_query($conn, $query)) {
        if (mysqli_num_rows($res) > 0) {

            while ($result = mysqli_fetch_assoc($res)) {
                $cid =  $result["cid"];
                $contact_id =  urlencode(base64_encode($cid));

                $html = "<tr id='row_$cid'>";
                if ($result["profile"] == "") {
                    $html .= "<td><div class='profile'></div></td>";
                } else {
                    $html .= "<td><div class='profile img'><img src='images/profile/" . $result["profile"] . "' alt=''></div></td>";
                }

                $html .= "<td class='name'>" . $result["name"] . "</td>";
                $html .= "<td>" . $result["phone"] . "</td>";

                $html .= "
                        <td>
                            <a href='contact-details?contact_id=$contact_id'>
                                <img src='images/chat.png' class='icon details-icon' alt='details icon' title='view all contact details'>
                            </a>
                        </td>
                        <td>
                            <a href='edit-contact?contact_id=$contact_id'><img src='images/edit.png' class='icon edit-icon' alt='edit icon' title='Edit contact details'>
                            </a>
                        </td>
                        <td>
                            <a href='#' class='delete' id='cid_$cid'><img src='images/delete.png' class='icon delete-icon' alt='delete icon' title='Delete contact'>
                            </a>
                        </td>";

                $html .= "</tr>";

                $response .= $html;
            }
            $pagination = '';
            if ($page != 1) {
                $pagination .= "<a href='#' page='" . $page - 1 . "' class=''>Prev</a>";
            }

            for ($i = 1; $i <= ceil($totalpage / $limit); $i++) {
                if ($page == $i)
                    $pagination .= "<a href='#' page='$i' class='active' disabled>$i</a>";
                else
                    $pagination .= "<a href='#' page='$i' class=''>$i</a>";
            }

            if ($page != ceil($totalpage / $limit)) {
                $pagination .= "<a href='#' page='". $page+1 ."' class=''>Next</a>";
            }

            echo json_encode(array("status" => 200, "response" => $response, "pagination" => $pagination));
        } else {
            $response .= "<tr> <td class='no-contacts' colspan='6'>Contacts Not Found </td></tr>";
            echo json_encode(array("status" => 200, "response" => $response, "pagination" => ""));
        }
    } else {
        $response .= "<tr> <td class='no-contacts' colspan='6'>Contacts Not Found </td></tr>";
        echo json_encode(array("status" => 200, "response" => $response, "pagination" => ""));
    }
}
