/*
 * ConfigReader.java
 *
 * Reader class for config files.
 *
 * author: Moritz W.
 * last edit / by: 2020-06-12 / Moritz W.
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

    private ConfigReader(){

    }
    public static ConfigReader getConfigReader() {
        if(configReader==null){
            configReader=new ConfigReader();
        }
        return configReader;
    }
    public void readConfigFile(){
        BufferedReader reader;
        String section;
        try{
            reader = new BufferedReader(new InputStreamReader(ConfigReader.class.getResourceAsStream("/config.txt")));
            String line = reader.readLine();
            while(line!= null){
                if(line.matches("\\[[[a-zA-Z0-9]]+\\]")){
                    section=line.substring(1,line.length()-1);
                }else if(line.matches("\\s*([a-zA-Z]+)\\s+\\=\\s+([^\\|\\<\\>\\;\\&\\$\\#\\!\\*\\`\\´\\?\\%\\\"\\§\\²\\³\\¼\\½\\¬\\{\\[\\]\\}\\′\\+\\=\\:]+)")){
                    String key = line.replaceAll("\\s","").split("=")[0];
                    String value = line.replaceAll("\\s","").split("=")[1];
                }else{

                }
                line=reader.readLine();
            }
            reader.close();
        }
        catch(Exception e){

        }
    }

}
