/*
 * ConfigReader.java
 *
 * Reader class for config files.
 *
 * author: Moritz W.
 * last edit / by: 2020-08-09 / Moritz W.
 */
package org.shiftplan.shiftPLANDesktop.Helper;

import java.io.BufferedReader;
import java.io.FileReader;
import java.io.InputStream;
import java.io.InputStreamReader;
import java.util.regex.Matcher;
import java.util.regex.Pattern;

public class ConfigReader {
    private static ConfigReader configReader=null;
    private String apiKey, apiLink, sessionKey;

    private ConfigReader(){
        apiKey="";
        apiLink="";
        sessionKey="";
    }
    public static ConfigReader getConfigReader() {
        if(configReader==null){
            configReader=new ConfigReader();
        }
        return configReader;
    }
    public void readConfigFile(){
        try{
            BufferedReader reader = new BufferedReader(new InputStreamReader(ConfigReader.class.getResourceAsStream("/config.txt")));
            String line = reader.readLine();
            while(line!= null){
                if(line.matches("\\s*([a-zA-Z]+)\\s+\\=\\s+([^\\|\\<\\>\\;\\&\\$\\#\\!\\*\\`\\´\\?\\%\\\"\\§\\²\\³\\¼\\½\\¬\\{\\[\\]\\}\\′\\+\\=\\:]+)")){
                    String key = line.replaceAll("\\s","").split("=")[0];
                    String value = line.replaceAll("\\s","").split("=")[1];
                    switch(key){
                        case "apiKey": apiKey=value;
                            break;
                        case "apiLink": apiLink=value;
                            break;
                        case "sessionKey": sessionKey=value;
                            break;
                        default: //Todo: Throw Exception
                            break;
                    }
                }else{
                    //ToDo: Throw Exception
                }
                line=reader.readLine();
            }
            reader.close();
        }
        catch(Exception e){

        }
    }
    public String GetAPIKey(){
        return apiKey;
    }
    public String GetAPILink(){
        return apiLink;
    }
    public String GetSessionKey(){
        return sessionKey;
    }
}
