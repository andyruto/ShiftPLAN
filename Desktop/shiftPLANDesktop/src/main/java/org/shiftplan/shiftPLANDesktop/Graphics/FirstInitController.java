/*
 * FirstInitController.java
 *
 * Controller for FirstInit.fxml.
 *
 * author: Sascha W.
 * last edit / by: 2020-08-08 / Sascha W.
 */
package org.shiftplan.shiftPLANDesktop.Graphics;

//Import statements
import com.jfoenix.controls.JFXButton;
import com.jfoenix.controls.JFXPasswordField;
import com.jfoenix.controls.JFXTextField;
import javafx.application.Platform;
import javafx.event.Event;
import javafx.event.EventHandler;
import javafx.fxml.FXML;
import javafx.fxml.FXMLLoader;
import javafx.scene.Parent;
import javafx.scene.Scene;
import javafx.scene.input.MouseEvent;
import javafx.stage.Stage;

public class FirstInitController {

    private LoginRahmenController parentController;

    @FXML
    protected JFXButton NextButton;

    @FXML
    protected JFXTextField APIURL;

    @FXML
    protected JFXPasswordField APIKey;

    @FXML
    protected void OnNextButtonClick(Event event) {
            parentController.setResource("/Login.fxml");
    }

    public void setParentController(LoginRahmenController parentController) {
        this.parentController = parentController;
    }

}
