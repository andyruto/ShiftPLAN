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
import javafx.event.ActionEvent;
import javafx.event.Event;
import javafx.event.EventHandler;
import javafx.fxml.FXML;
import javafx.scene.Scene;
import javafx.stage.Stage;

public class AddUserController {

    private UsersController parentController;

    @FXML
    protected JFXTextField UserName;

    @FXML
    protected JFXTextField WeeklyWorkingMinutes;

    @FXML
    protected JFXTextField WorkingWeekDays;

    @FXML
    protected JFXTextField YearVacationDays;

    @FXML
    protected JFXButton AddUserButton;

    @FXML
    protected JFXButton CancelUserButton;

    @FXML
    protected JFXTextField UserPassword;

    @FXML
    protected void OnAddUserButtonClick(Event event) {
        parentController.closeDialog();
    }

    @FXML
    protected void OnCancelUserButtonClick(Event event) {
        parentController.closeDialog();
    }

    public void setParentController(UsersController parentController) {
        this.parentController = parentController;
    }

}
