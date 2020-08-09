///*
// * APIFrontend.java
// *
// * The main entry point for the application and the related variables and methods.
// *
// * author: Maximilian T. | Kontr0x
// * last edit / by: 2020-08-09 / Maximilian T. | Kontr0x
// */
//package org.shiftplan.shiftPLANDesktop;
//
//import java.text.SimpleDateFormat;
//import java.util.Calendar;
//import java.util.HashMap;
//
//public class APIFrontend {
//
//    private static String SyntaxHelper(String key, String value){
//        return "\""+key+"\": \""+value+"\", ";
//    }
//
//    public static String Packager(HashMap<String, String> HMap){
//        String jsonString = "";
//        switch(HMap.get("action")){
//            case "create":
//                jsonString+="{\"action\": \"create\", ";
//                    switch(HMap.get("typeOfObject")){
//                    case "user":
//                        jsonString+=SyntaxHelper("name", HMap.get("name"))
//                                    +SyntaxHelper("weekly_working_minutes", HMap.get("weekly_working_minutes"))
//                                    +SyntaxHelper("working_week_days", HMap.get("working_week_days")
//                                    +SyntaxHelper("year_vacation_days", HMap.get("year_vacation_days")
//                                    +SyntaxHelper("password_hash", HMap.get("password_hash")
//                                    +"\"timeStamp\": \""+new SimpleDateFormat("yyyy/MM/dd_HH:mm:ss").format(Calendar.getInstance().getTime())+"\"}";
//                        return jsonString;
//                        break;
//                    case "session":
//                        jsonString+=SyntaxHelper("expiration_date", HMap.get("expiration_date")
//                                    +SyntaxHelper("user_id", HMap.get("user_id"))
//                                    +"\"timeStamp\": \""+new SimpleDateFormat("yyyy/MM/dd_HH:mm:ss").format(Calendar.getInstance().getTime())+"\"}";
//                        return jsonString;
//                        break;
//                    }
//                break;
//            case "modify":
//                break;
//            case "delete":
//                break;
//            default:
//                break;
//        }
//        if(HMap.get("action"))
//        String jsonString = "";
//
//    }
//
//    public static APIconnector() {}
//}