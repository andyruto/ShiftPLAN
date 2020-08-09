package org.shiftplan.shiftPLANDesktop.Helper;

import java.io.BufferedWriter;
import java.io.FileWriter;
import java.io.OutputStreamWriter;

public class ConfigWriter {
    private static ConfigWriter configWriter=null;

    private ConfigWriter(){

    }
    public static ConfigWriter getConfigWriter() {
        if(configWriter==null){
            configWriter=new ConfigWriter();
        }
        return configWriter;
    }
    public boolean writeConfigFile(String apiKey,String apiLink, String sessionKey) {
        try{
            BufferedWriter writer = new BufferedWriter(new FileWriter("src/main/resources/config.txt"));
            writer.write("[API]\napiKey = "+apiKey+"\napiLink = "+apiLink+"\n\n[Session]\nsessionKey = "+sessionKey);
            writer.close();
            return true;
        }
        catch (Exception e){
            System.out.println("not working");
            return false;
        }
    }
}