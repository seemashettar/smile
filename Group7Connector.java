package ca.ubc.magic.coffeeshop.connectors;

import java.io.IOException;
import java.net.InetAddress;
import java.net.InetSocketAddress;
import java.net.UnknownHostException;
import java.nio.ByteBuffer;
import java.nio.channels.SelectionKey;
import java.nio.channels.Selector;
import java.nio.channels.SocketChannel;
import java.util.HashMap;
import java.util.Iterator;
import java.util.Map;
import java.util.Set;

import ca.ubc.magic.coffeeshop.classes.CoffeeShop;
import ca.ubc.magic.coffeeshop.jaxb.Application;
import ca.ubc.magic.osgibroker.OSGiBrokerClient;
import ca.ubc.magic.osgibroker.OSGiBrokerException;
import ca.ubc.magic.osgibroker.OSGiBrokerService;
import ca.ubc.magic.osgibroker.workgroups.TopicEvent;

public class Group7Connector implements Connector {
	
	CoffeeShop cs;
	String topic = "Group7";
	String topicConnector = "cs_Group7";

	OSGiBrokerService BrokerGroup7;
	OSGiBrokerClient ClientGroup7;
		
	public Group7Connector() throws OSGiBrokerException {
				
		try {
			cs = CoffeeShop.getInstance();			
						
			BrokerGroup7 = new OSGiBrokerService("localhost:8800");
			ClientGroup7 = BrokerGroup7.addClient("Group7connector");
			
			try {
				ClientGroup7.subscriber().subscribeHttp("Group7", false);
				ClientGroup7.subscriber().subscribeHttp("cs_Group7", false);
								
			} catch (OSGiBrokerException e1) {
				e1.printStackTrace();
			}
			
			Map<String, String> map = new HashMap<String, String>();
			map.put("message", "CONSTRUCTOR");
			sendEvent(map);
			
			new Thread(new SocialwallListener()).start();
			
		}
		catch (UnknownHostException e) {
			e.printStackTrace();
		}
		catch (IOException e) {
			e.printStackTrace();
		}
		
		
		
	}
	
	class SocialwallListener implements Runnable {
		
		@Override
		public void run() {
								
			while (true){
				try {
					TopicEvent[] events = ClientGroup7.subscriber().getEvents("Group7", 1);
							
					if ( events.length > 0 ) { // if we have at least one event.
						Map<String, String> map = new HashMap<String, String>();
						map.put("message", "RUNNINGSTILL" );
						ClientGroup7.publisher().sendEvent("cs_Group7", map);							
					}

					Thread.sleep(5000);
					
				} catch (InterruptedException e) {
					e.printStackTrace();
				}
				catch (OSGiBrokerException e1) {
					e1.printStackTrace();
				}					
			}
			
		}
	}
	
	@Override
	public void receiveEvent(TopicEvent event) {
		// Nothing to do here. No need to do anything on receive.
	}
	
	@Override
	public void sendEvent(Map<String, String> paramaters) {
		//
	}

	
}
