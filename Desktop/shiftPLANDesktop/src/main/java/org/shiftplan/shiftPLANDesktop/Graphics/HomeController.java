/*
 * HomeController.java
 *
 * Controller for Home.fxml.
 *
 * author: Sascha W.
 * last edit / by: 2020-08-08 / Sascha W.
 */
package org.shiftplan.shiftPLANDesktop.Graphics;

//Import statements
import com.jfoenix.controls.JFXDialog;
import javafx.fxml.FXML;
import javafx.fxml.FXMLLoader;
import javafx.scene.control.Label;
import javafx.scene.layout.StackPane;

/**
 * Controller for the Home xml file.
 */
public class HomeController {
    
    //Initialize variables
    private JFXDialog loading;
    
    //Insert all fx id's
    @FXML
    private Label DayDisplay;

    @FXML
    private Label MonthDisplay;

    @FXML
    private Label ShiftDisplay;

    @FXML
    private Label LengthDisplay;

    @FXML
    private Label SupervisorDisplay;
    
    @FXML
    private StackPane HomeStackPane;

    //Show and load loading screen
    private void showLoading() {
        try {
            FXMLLoader stackPaneLoader = new FXMLLoader(getClass().getResource("/Loading.fxml"));
            loading = new JFXDialog(HomeStackPane, stackPaneLoader.load(), JFXDialog.DialogTransition.CENTER);
            loading.setOverlayClose(false);
            loading.show();
        }catch(Exception e) {}
    }
    private void hideLoading() {
        loading.close();
    }
}
