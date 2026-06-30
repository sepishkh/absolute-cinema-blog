<?php

class Paths {
    public static string $BASE;

    public static string $INIT_SCHEMA;

    public static string $ABSCIN;
    public static string $CSS;
    public static string $INDEX;    
    public static string $LOGIN;   
    public static string $NEW;   
    public static string $PROFILE;  
    public static string $SIGNUP; 
    public static string $VIEW;
    public static string $ROUTE;

    public static string $APPR_CMNT;
    public static string $PROC_CMNT;
    public static string $PROC_LOGIN;
    public static string $PROC_NEW;
    public static string $PROC_SIGNUP;
    public static string $SQLDB;     
    public static string $DBHELPER;     
    public static string $UTILZ;       

    public static string $P404;
    public static string $FOOTER;
    public static string $HEADER;
    public static string $POST_CARD_TEMPLATE;

    public static function init() {
        self::$BASE = dirname(__DIR__);

        self::$INIT_SCHEMA   = self::$BASE . "/data/init.sql";

        self::$CSS      = "/css/style.css";
        self::$ABSCIN   = "/images/abscin.jpg";
        self::$INDEX    = "index.php";
        self::$LOGIN    = "/login.php";
        self::$NEW      = "/new.php";
        self::$PROFILE  = "/profile.php";
        self::$SIGNUP   = "/signup.php";
        self::$VIEW     = "/view.php";
        self::$ROUTE    = "/route.php";

        self::$APPR_CMNT    = self::$BASE . "/src/approve-comment.php";
        self::$PROC_CMNT    = self::$BASE . "/src/process-comment.php";
        self::$PROC_LOGIN   = self::$BASE . "/src/process-login.php";
        self::$PROC_NEW     = self::$BASE . "/src/process-new.php";
        self::$PROC_SIGNUP  = self::$BASE . "/src/process-signup.php";
        self::$SQLDB        = self::$BASE . "/src/sqldb.php";
        self::$DBHELPER     = self::$BASE . "/src/dbhelper.php";
        self::$UTILZ        = self::$BASE . "/src/utilz.php";
        
        self::$P404                 = self::$BASE . "/templates/404.php";
        self::$FOOTER               = self::$BASE . "/templates/footer.php";
        self::$HEADER               = self::$BASE . "/templates/header.php";
        self::$POST_CARD_TEMPLATE   = self::$BASE . "/templates/post-card-template.php";
    }
}
