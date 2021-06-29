/**
 * tasks-input.component.spec
 * 
 * Typescript class containing the tests for the 
 * tasks-input component.
 * 
 * author: Anne Naumann
 * last edit / by: 2021-06-15 / Anne Naumann
 */
import { ComponentFixture, TestBed } from '@angular/core/testing';
import { TasksInputComponent } from './tasks-input.component';

describe('TasksInputComponent', () => {
  let component: TasksInputComponent;
  let fixture: ComponentFixture<TasksInputComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ TasksInputComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(TasksInputComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
