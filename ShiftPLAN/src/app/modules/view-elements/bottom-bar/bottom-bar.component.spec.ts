
/**
 * bottom-bar.component.spec
 * 
 * Typescript class containing the tests for the 
 * bottom-bar component.
 * 
 * author: Anne Naumann
 * last edit / by: 2021-05-26 / Anne Naumann
 */
import { ComponentFixture, TestBed } from '@angular/core/testing';
import { BottomBarComponent } from './bottom-bar.component';

describe('BottomBarComponent', () => {
  let component: BottomBarComponent;
  let fixture: ComponentFixture<BottomBarComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ BottomBarComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(BottomBarComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
