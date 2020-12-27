<?php

if (FOLDER_NAME == "public") {

} else if (FOLDER_NAME == "docs") {


} else if (FOLDER_NAME == "users") {
    if (!($_SESSION['role'] == 0)) {
        include_once '../403.shtml';
        exit;
    }
} else if (FOLDER_NAME == "student") {
    // Anyone can access
} else if (FOLDER_NAME == "home") {
    // allow
} else if (FOLDER_NAME == "view") {
    // allow
} else if (FOLDER_NAME == "profile") {
    // allow
} else if (FOLDER_NAME == "docsAdmin") {
    // Only allowed for admin and data enter
    if (!($_SESSION['role'] == 0 || $_SESSION['role'] == 4)) {
        include_once '../403.shtml';
        exit;
    }
} else if (FOLDER_NAME == "profileAdmin") {
    // Allow for admins or lecturers
    if (!($_SESSION['role'] == 0 || $_SESSION['role'] == 2)) {
        include_once '../403.shtml';
        exit;
    }
} else {
    include_once '../404.shtml';
    exit;
}
