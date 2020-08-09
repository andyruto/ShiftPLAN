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
import com.jfoenix.controls.JFXTextField;
import javafx.event.Event;
import javafx.fxml.FXML;

/**
 * Controller for the AddUser xml file.
 */
public class AddUserController {

    //Initialize parentcontroller for AddUser
    private UsersController parentController;

    //Insert all fx id's
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

    //Adding all events
    @FXML
    protected void OnAddUserButtonClick(Event event) {
        parentController.closeDialog();
    }

    @FXML
    protected void OnCancelUserButtonClick(Event event) {
        parentController.closeDialog();
    }

    //Set parentcontroller for AddUser
    public void setParentController(UsersController parentController) {
        this.parentController = parentController;
    }

}
