/*
 * FirstInit.java
 *
 * GUI for API-Initiation-Screen.
 *
 * author: Sascha W.
 * last edit / by: 2020-08-06 / Sascha W.
 */
package org.shiftplan.shiftPLANDesktop.Graphics;

//Import statements
import javafx.application.Application;
import javafx.fxml.FXMLLoader;
import javafx.scene.Parent;
import javafx.scene.Scene;
import javafx.stage.Stage;
import javafx.event.ActionEvent;
import javafx.fxml.FXML;
import javafx.scene.text.Text;
import javafx.stage.StageStyle;

/**
 * GUI for the screen to setup the API connection with API-URL and API-Key.
 */
public class FirstInit extends Application{

    public static void main(String[] args) {
        launch(args);
    }

    @Override
    public void start(Stage stage) throws Exception {
        //Making Window without Border
        stage.initStyle(StageStyle.UNDECORATED);

        //Load XML
        Parent root = FXMLLoader.load(getClass().getResource("FirstInit.fxml"));
        Scene firstInit = new Scene(root);
        stage.setScene(firstInit);
        stage.show();
    }
}
