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

    //define your offsets here
    private double xOffset = 0;
    private double yOffset = 0;

    @Override
    public void start(Stage stage) throws Exception {

        //Making Window without Border
        stage.initStyle(StageStyle.UNDECORATED);
        stage.setResizable(false);

        Parent root = FXMLLoader.load(getClass().getResource("/LoginRahmen.fxml"));



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

        Scene scene = new Scene (root);
        stage.setScene(scene);
        stage.show();
    }
}
