/*
 * LoginBorderController.java
 *
 * Controller for LoginBorder.fxml.
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

/**
 * Controller for the LoginBorder xml file.
 */
public class LoginBorderController {

    //Insert all fx id's
    @FXML
    private JFXButton ExitButton;

    @FXML
    private JFXButton MinimizeButton;

    @FXML
    private Pane LoginScreen;

    //Set parentcontroller for FirstInit
    public void initialize()  {
        try {
            FXMLLoader loginScreenLoader = new FXMLLoader(getClass().getResource("/FirstInit.fxml"));
            LoginScreen.getChildren().add(loginScreenLoader.load());
            loginScreenLoader.<FirstInitController>getController().setParentController(this);
        }catch(Exception e) {}
    }

    //Adding all events
    @FXML
    private void OnExitButtonClick(Event event) {
        Platform.exit();
    }

    @FXML
    private void OnMinimizeButtonClick(Event event) {
        ((Stage)((JFXButton)event.getSource()).getScene().getWindow()).setIconified(true);
    }

    //Load new resource into LoginScreen
    protected void setResource(String resource) {
        try {
            LoginScreen.getChildren().clear();
            LoginScreen.getChildren().add(FXMLLoader.load(getClass().getResource(resource)));
        }catch(Exception e) {}
    }
}
