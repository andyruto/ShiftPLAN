/*
 * AddUserController.java
 *
 * Controller for AddUser.fxml.
 *
 * author: Sascha W.
 * last edit / by: 2020-08-08 / Sascha W.
 */
package org.shiftplan.shiftPLANDesktop.Graphics;

//Import statements
import com.jfoenix.controls.JFXButton;
import com.jfoenix.controls.JFXDialog;
import com.jfoenix.controls.JFXTextField;
import javafx.event.Event;
import javafx.fxml.FXML;
import javafx.fxml.FXMLLoader;
import javafx.scene.layout.StackPane;

/**
 * Controller for the AddUser xml file.
 */
public class AddUserController {

    //Initialize parentcontroller for AddUser
    private UsersController parentController;

    //Initialize variables
    private JFXDialog loading;

    //Insert all fx id's
    @FXML
    private JFXTextField Username;

    @FXML
    private JFXTextField WeeklyWorkingMinutes;

    @FXML
    private JFXTextField WorkingWeekDays;

    @FXML
    private JFXTextField YearVacationDays;

    @FXML
    private JFXButton AddUserButton;

    @FXML
    private JFXButton CancelUserButton;

    @FXML
    private JFXTextField UserPassword;

    @FXML
    private StackPane AddUserStackPane;

    //Adding all events
    @FXML
    private void OnAddUserButtonClick(Event event) {
        parentController.closeDialog();
    }

    @FXML
    private void OnCancelUserButtonClick(Event event) {
        parentController.closeDialog();
    }

    //Set parentcontroller for AddUser
    protected void setParentController(UsersController parentController) {
        this.parentController = parentController;
    }

    //Show and load loading screen
    private void showLoading() {
        try {
            FXMLLoader stackPaneLoader = new FXMLLoader(getClass().getResource("/Loading.fxml"));
            loading = new JFXDialog(AddUserStackPane, stackPaneLoader.load(), JFXDialog.DialogTransition.CENTER);
            loading.setOverlayClose(false);
            loading.show();
        }catch(Exception e) {}
    }
    private void hideLoading() {
        loading.close();
    }
}
