/*
 * HttpConnector.java
 *
 * Connector between API and application.
 *
 * author: Sascha W.
 * last edit / by: 2020-08-09 / Sascha W.
 */
package org.shiftplan.shiftPLANDesktop.Logics.Core_Classes;

import org.json.JSONObject;
import java.io.BufferedReader;
import java.io.InputStreamReader;
import java.io.OutputStream;
import java.net.HttpURLConnection;
import java.net.URL;

public class HttpConnector {
    private static HttpConnector httpConnector = null;

    private HttpConnector() {
    }

    public static HttpConnector getHttpConnector() {
        if(httpConnector==null){
            httpConnector=new HttpConnector();
        }
        return httpConnector;
    }
    
    public JSONObject request(String targetUrl, String json) {

        String answer = new String();

        try {
            URL url = new URL(targetUrl);
            HttpURLConnection con = (HttpURLConnection)url.openConnection();
            con.setRequestMethod("POST");
            con.setRequestProperty("Content-Type", "application/json; utf-8");
            con.setRequestProperty("Accept", "application/json");
            con.setDoOutput(true);

            try(OutputStream os = con.getOutputStream()) {
                byte[] input = json.getBytes("utf-8");
                os.write(input, 0, input.length);
            }

            try(BufferedReader br = new BufferedReader(
                    new InputStreamReader(con.getInputStream(), "utf-8"))) {
                StringBuilder response = new StringBuilder();
                String responseLine = null;
                while ((responseLine = br.readLine()) != null) {
                    response.append(responseLine.trim());
                }
                answer = response.toString();
            }
        }catch(Exception e) {}
        return new JSONObject(answer);
    }
}
