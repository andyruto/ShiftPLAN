/*
 * LoginRahmenController.java
 *
 * Controller for LoginRahmen.fxml.
 *
 * author: Sascha W.
 * last edit / by: 2020-08-08 / Sascha W.
 */
package org.shiftplan.shiftPLANDesktop.Graphics;

//Import statements

import com.jfoenix.controls.JFXButton;
import javafx.application.Platform;
import javafx.event.Event;
import javafx.fxml.FXML;
import javafx.fxml.FXMLLoader;
import javafx.scene.layout.Pane;
import javafx.stage.Stage;

public class LoginRahmenController {

    @FXML
    protected JFXButton ExitButton;

    @FXML
    protected JFXButton MinimizeButton;

    @FXML
    protected Pane LoginScreen;

    @FXML
    protected void OnExitButtonClick(Event event) {
        Platform.exit();
    }

    @FXML
    protected void OnMinimizeButtonClick(Event event) {
        ((Stage)((JFXButton)event.getSource()).getScene().getWindow()).setIconified(true);
    }

    public void initialize()  {
        try {
            FXMLLoader loginScreenLoader = new FXMLLoader(getClass().getResource("/FirstInit.fxml"));
            LoginScreen.getChildren().add(loginScreenLoader.load());
            loginScreenLoader.<FirstInitController>getController().setParentController(this);
        }catch(Exception e) {}
    }

    public void setResource(String resource) {
        try {
            LoginScreen.getChildren().clear();
            LoginScreen.getChildren().add(FXMLLoader.load(getClass().getResource(resource)));
        }catch(Exception e) {}
    }
}
