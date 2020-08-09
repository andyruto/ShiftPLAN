/*
 * Main.java
 *
 * The main entry point for the application and the related variables and methods.
 *
 * author: Andreas G.
 * last edit / by: 2020-08-08 / Sascha W.
 */
package org.shiftplan.shiftPLANDesktop;

//Import statements
import javafx.application.Application;
import javafx.event.EventHandler;
import javafx.fxml.FXMLLoader;
import javafx.scene.Parent;
import javafx.scene.Scene;
import javafx.scene.input.MouseEvent;
import javafx.stage.Stage;
import javafx.stage.StageStyle;

/**
 * A small shift planning project. Class containing the main entry point for the application.
 */
public class Main extends Application {

    /**
     * The main entry point for the application.
     *
     * @param args Startup arguments provided at application startup
     */
    public static void main(String[] args) {
        launch(args);
    }

    //Initialize variables
    private double xOffset = 0;
    private double yOffset = 0;

    /**
     * Start of stage.
     *
     * @param stage initialized
     * @throws Exception
     */
    @Override
    public void start(Stage stage) throws Exception {

        //Making window without border and not resizable
        stage.initStyle(StageStyle.UNDECORATED);
        stage.setResizable(false);

        //Load xml file into parent root
        Parent root = FXMLLoader.load(getClass().getResource("/LoginBorder.fxml"));

        //Make Window movable
        root.setOnMousePressed(new EventHandler<MouseEvent>() {
            @Override
            public void handle(MouseEvent event) {
                xOffset = event.getSceneX();
                yOffset = event.getSceneY();
            }
        });
        root.setOnMouseDragged(new EventHandler<MouseEvent>() {
            @Override
            public void handle(MouseEvent event) {
                stage.setX(event.getScreenX() - xOffset);
                stage.setY(event.getScreenY() - yOffset);
            }
        });

        //Insert into stage and show it
        Scene scene = new Scene (root);
        stage.setScene(scene);
        stage.show();
    }
}
