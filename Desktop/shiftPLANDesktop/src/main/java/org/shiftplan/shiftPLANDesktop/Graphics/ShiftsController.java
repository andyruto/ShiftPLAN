/*
 * ShiftsController.java
 *
 * Controller for Shifts.fxml.
 *
 * author: Sascha W.
 * last edit / by: 2020-08-08 / Sascha W.
 */
package org.shiftplan.shiftPLANDesktop.Graphics;

//Import statements
import com.jfoenix.controls.JFXDatePicker;
import com.jfoenix.controls.JFXListView;
import com.jfoenix.controls.JFXTextField;
import javafx.fxml.FXML;

/**
 * Controller for the Shifts xml file.
 */
public class ShiftsController {

    //Insert all fx id's
    @FXML
    protected JFXTextField UserSearchBar;

    @FXML
    protected JFXDatePicker DateSearchBar;

    @FXML
    protected JFXListView UserList;

    @FXML
    protected JFXListView Calender;

}
