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
import com.jfoenix.controls.JFXDialog;
import com.jfoenix.controls.JFXPasswordField;
import com.jfoenix.controls.JFXTextField;
import javafx.event.Event;
import javafx.fxml.FXML;
import javafx.fxml.FXMLLoader;
import javafx.scene.layout.StackPane;

/**
 * Controller for the FirstInit xml file.
 */
public class FirstInitController {

    //Initialize Variables
    private JFXDialog loading;

    //Initialize parentcontroller for AddUser
    private LoginBorderController parentController;

    //Insert all fx id's
    @FXML
    private JFXButton NextButton;

    @FXML
    private JFXTextField APIURL;

    @FXML
    private JFXPasswordField APIKey;

    @FXML
    private StackPane FirstInitStackPane;

    //Adding all events
    @FXML
    private void OnNextButtonClick(Event event) {
        parentController.setResource("/Login.fxml");
    }

    //Set parentcontroller for LoginBorder
    protected void setParentController(LoginBorderController parentController) {
        this.parentController = parentController;
    }

    //Show and load loading screen
    private void showLoading() {
        try {
            FXMLLoader stackPaneLoader = new FXMLLoader(getClass().getResource("/Loading.fxml"));
            loading = new JFXDialog(FirstInitStackPane, stackPaneLoader.load(), JFXDialog.DialogTransition.CENTER);
            loading.setOverlayClose(false);
            loading.show();
        }catch(Exception e) {}
    }
    private void hideLoading() {
        loading.close();
    }
}
