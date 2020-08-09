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
import javafx.event.Event;
import javafx.fxml.FXML;

/**
 * Controller for the FirstInit xml file.
 */
public class FirstInitController {

    //Initialize parentcontroller for AddUser
    private LoginBorderController parentController;

    //Insert all fx id's
    @FXML
    protected JFXButton NextButton;

    @FXML
    protected JFXTextField APIURL;

    @FXML
    protected JFXPasswordField APIKey;

    //Adding all events
    @FXML
    protected void OnNextButtonClick(Event event) {
            parentController.setResource("/Login.fxml");
    }

    //Set parentcontroller for LoginBorder
    public void setParentController(LoginBorderController parentController) {
        this.parentController = parentController;
    }

}
