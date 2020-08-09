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
import com.jfoenix.controls.JFXDialog;
import com.jfoenix.controls.JFXListView;
import com.jfoenix.controls.JFXTextField;
import javafx.fxml.FXML;
import javafx.fxml.FXMLLoader;
import javafx.scene.layout.StackPane;

/**
 * Controller for the Shifts xml file.
 */
public class ShiftsController {

    //Initialize variables
    private JFXDialog loading;
    
    //Insert all fx id's
    @FXML
    private JFXTextField UserSearchBar;

    @FXML
    private JFXDatePicker DateSearchBar;

    @FXML
    private JFXListView UserList;

    @FXML
    private JFXListView Calender;
    
    @FXML
    private StackPane ShiftsStackPane;

    //Show and load loading screen
    private void showLoading() {
        try {
            FXMLLoader stackPaneLoader = new FXMLLoader(getClass().getResource("/Loading.fxml"));
            loading = new JFXDialog(ShiftsStackPane, stackPaneLoader.load(), JFXDialog.DialogTransition.CENTER);
            loading.setOverlayClose(false);
            loading.show();
        }catch(Exception e) {}
    }
    private void hideLoading() {
        loading.close();
    }
}
