/*
 * ConfigReader.java
 *
 * Reader class for config files.
 *
 * author: Moritz W.
 * last edit / by: 2020-06-12 / Moritz W.
 */
package org.shiftplan.shiftPLANDesktop.Helper;

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
}
